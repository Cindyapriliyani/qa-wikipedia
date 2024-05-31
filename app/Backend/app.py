from flask import Flask, request, jsonify
from transformers import BertTokenizer, BertForQuestionAnswering
import torch
import wikipediaapi

app = Flask(__name__)

tokenizer = BertTokenizer.from_pretrained("indobenchmark/indobert-large-p1")
model = BertForQuestionAnswering.from_pretrained("indobenchmark/indobert-large-p1")

@app.route('/api/answer', methods=['POST'])
def answer():
    data = request.json
    question = data['question']
    context = data['context']

    inputs = tokenizer(question, context, return_tensors='pt')
    outputs = model(**inputs)
    answer_start_index = torch.argmax(outputs.start_logits)
    answer_end_index = torch.argmax(outputs.end_logits) + 1
    answer = tokenizer.convert_tokens_to_string(tokenizer.convert_ids_to_tokens(inputs.input_ids[0][answer_start_index:answer_end_index]))

    return jsonify({'answer' : answer})

if _name_ == '_main_':
    app.run(port=5000)
