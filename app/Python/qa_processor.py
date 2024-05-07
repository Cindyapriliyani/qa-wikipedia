from transformers import AutoModelForQuestionAnswering, AutoTokenizer

class IndoBertQAProcessor:
    def __init__(self):
        self.model_name = "cahya/indobert-base-p1"
        self.tokenizer = AutoTokenizer.from_pretrained_model(self.model_name)
        self.model = AutoModelForQuestionAnswering.from_pretrained(self.model_name)
        

    def answer_question(self, question, context):
        inputs = self.tokenizer(question, context, return_tensors="pt")
        with torch.no_grad():
            outputs = self.model(**inputs)
        answer_start_scores = outputs.start_logits
        answer_end_scores = outputs.end_logits
        answer_start = torch.argmax(answer_start_scores)
        answer_end = torch.argmax(answer_end_scores) + 1
        answer = self.tokenizer.convert_tokens_to_string(self.tokenizer.convert_ids_to_tokens(inputs["input_ids"][0][answer_start:answer_end]))
        return answer
