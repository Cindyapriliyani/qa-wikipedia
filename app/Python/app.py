from flask import Flask, request, jsonify
from transformers import AutoTokenizer, AutoModelForQuestionAnswering
import torch


app = Flask(__name__)

#Load IndoBERT model and tokenizer
tokenizer = AutoTokenizer.from_pretrained("indobenchmark/indobert-base-p1")
model = AutoModelForQuestionAnswering.from_pretrained("indobenchmark/indobert-base-p1")


def answer_question(question, context):
    inputs = tokenizer.encode_plus(question, context, return_tensors="pt")
    input_ids = inputs["input_ids"].tolist()[0]

    outputs = model(**inputs)
    answer_start_scores = outputs.start_logits
    answer_end_scores = outputs.end_logits

    answer_start = torch.argmax(answer_start_scores)
    answer_end = torch.argmax(answer_end_scores) + 1

    answer = tokenizer.convert_tokens_to_string(tokenizer.convert_ids_to_tokens(input-ids[answer_start:answer_end]))
    return answer


@app.route('/api/answer', methods=['POST'])
def get_answer():
    data = request.json
    question = data.get('question')
    context = data.get('context')
    answer = answer_question(question, context)
    return jsonify({"answer": answer})

if __name__ == '__main__':
    app.run(debug=True)
