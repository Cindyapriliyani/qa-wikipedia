
import collections
import datasets
import torch

import numpy as np
import pandas as pd
import wikipediaapi

from datasets import Dataset
from datasets.utils.logging import set_verbosity_error

from transformers import AutoTokenizer
from transformers import AutoModelForQuestionAnswering

datasets.disable_progress_bar()
set_verbosity_error()

class Pipeline:
    """
    Utility to build prepare model and predict question answering task

    Args:
        - model_checkpoint: path to the model located (local path or huggingface path)
        - max_length: The maximum length of a feature (question and context)
        - doc_stride: The authorized overlap between two part of the context when splitting it is needed.
        - impossible_answer: make this model to predict if the question is related to the context or not
    """

    def __init__(self, model_checkpoint="Rifky/Indobert-QA", max_length=384, doc_stride=128, impossible_answer=False):
        self.__model = AutoModelForQuestionAnswering.from_pretrained(model_checkpoint)

        self.__tokenizer = AutoTokenizer.from_pretrained(model_checkpoint)

        self.__MAX_LENGTH = max_length
        self.__DOC_STRIDE = doc_stride

        self.__PAD_ON_RIGHT = self.__tokenizer.padding_side == "right"
        self.__impossible_answer = impossible_answer

    def __preprocess(self, data):
         
       data["question"] = [question.lstrip() for question in data["question"]]
        
        tokenized_examples = self.__tokenizer(
            data["question"] if self.__PAD_ON_RIGHT else data["context"],
            data["context"] if self.__PAD_ON_RIGHT else data["question"],
            truncation="only_second" if self.__PAD_ON_RIGHT else "only_first",
            max_length=self.__MAX_LENGTH,
            stride=self.__DOC_STRIDE,
            return_overflowing_tokens=True,
            return_offsets_mapping=True,
            padding="max_length",
        )

        sample_mapping = tokenized_examples.pop("overflow_to_sample_mapping")
        tokenized_examples["example_id"] = []

        for i in range(len(tokenized_examples["input_ids"])):
            sequence_ids = tokenized_examples.sequence_ids(i)
            context_index = 1 if self.__PAD_ON_RIGHT else 0
            sample_index = sample_mapping[i]
            tokenized_examples["example_id"].append(data["id"][sample_index])
            tokenized_examples["offset_mapping"][i] = [
                (o if sequence_ids[k] == context_index else None)
                for k, o in enumerate(tokenized_examples["offset_mapping"][i])
            ]

        return tokenized_examples

    def __postprocess(self, raw_data, features, raw_predictions, n_best_size=10, max_answer_length=100):
        """
        Postprocess the output of the model into readable text and score
        
        Args:
            - raw_data: the data we want to predict before taking into any process
            - features: data that already preprocessed
            - raw_predictions: output of the model prediction
            - n_best_size: number of best answers we want to consider as predictions
            - max_answer_length: maximum length of the answer

        Output:
            - predictions: best answers
            - answers: list of all the model answers
        """

        all_start_logits, all_end_logits = raw_predictions
        example_id_to_index = {k: i for i, k in enumerate(raw_data["id"])}
        features_per_example = collections.defaultdict(list)
        
        for i, feature in enumerate(features):
            features_per_example[example_id_to_index[feature["example_id"]]].append(i)
        
        predictions = collections.OrderedDict()

        for example_index, example in enumerate(raw_data):
            feature_indices = features_per_example[example_index]
            min_null_score = None
            valid_answers = []
            context = example["context"]

            for feature_index in feature_indices:
                start_logits = all_start_logits[feature_index]
                end_logits = all_end_logits[feature_index]
                offset_mapping = features[feature_index]["offset_mapping"]
                cls_index = features[feature_index]["input_ids"].index(self.__tokenizer.cls_token_id)
                feature_null_score = start_logits[cls_index] + end_logits[cls_index]
                
                if min_null_score is None or min_null_score < feature_null_score:
                    min_null_score = feature_null_score

                start_indexes = np.argsort(start_logits)[-1 : -n_best_size - 1 : -1].tolist()
                end_indexes = np.argsort(end_logits)[-1 : -n_best_size - 1 : -1].tolist()
                
                for start_index in start_indexes:
                    for end_index in end_indexes:
                        if (
                            start_index >= len(offset_mapping)
                            or end_index >= len(offset_mapping)
                            or offset_mapping[start_index] is None
                            or offset_mapping[end_index] is None
                        ):
                            continue
                        if end_index < start_index or end_index - start_index + 1 > max_answer_length:
                            continue
                        start_char = offset_mapping[start_index][0]
                        end_char = offset_mapping[end_index][1]
                        valid_answers.append({
                            "score": start_logits[start_index] + end_logits[end_index],
                            "text": context[start_char:end_char],
                        })

            if len(valid_answers) > 0:
                answers = sorted(valid_answers, key=lambda x: x["score"], reverse=True)
                best_answer = answers[0]
            else:
                best_answer = {"text": "", "score": 0.0}

            if not self.__impossible_answer:
                predictions[example["id"]] = best_answer["text"]
            else:
                answer = best_answer["text"] if best_answer["score"] > min_null_score else ""
                predictions[example["id"]] = answer

        return {
            "best answer": predictions,
            "answers": answers
        }

    def predict(self, context, questions):
        """
        Predict the answer of a question from a given context
        
        Args:
            - context: context of the question
            - questions: questions that we want the model to answer

        Output:
            - Best answer
            - List of answers
        """

        data = []

        if isinstance(questions, list):
            for i, question in enumerate(questions):
                data_temp = {"id": i, "context": context, "question": question}
                data.append(data_temp)
        else:
            data.append({"id": 0, "context": context, "question": questions})

        data = Dataset.from_pandas(pd.DataFrame(data))
        data_feature = data.map(self.__preprocess, batched=True, remove_columns=data.column_names)

        temp_data_feature = {'input_ids': [], 'token_type_ids': [], 'attention_mask': []}
        for i in data_feature:
            for k, v in i.items():
                if k in temp_data_feature.keys():
                    temp_data_feature[k].append(v)

        raw_prediction = self.__model(**{k: torch.tensor(v) for k, v in temp_data_feature.items()})
        del temp_data_feature

        return self.__postprocess(data, data_feature, [raw_prediction.start_logits.detach().numpy(), raw_prediction.end_logits.detach().numpy()])

def get_wikipedia_content(title, lang='en'):
    wiki_wiki = wikipediaapi.Wikipedia(lang)
    page = wiki_wiki.page(title)
    if page.exists():
        return page.text
    else:
        return None

# Contoh Penggunaan
title = "Indonesia"
context = get_wikipedia_content(title)
questions = ["Apa ibu kota Indonesia?", "Siapa presiden Indonesia?"]

pipeline = Pipeline()
result = pipeline.predict(context, questions)

print(result)