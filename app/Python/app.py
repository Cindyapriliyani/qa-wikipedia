from flask import Flask, request, jsonify
from qa_processor import IndoBertQAProcessor

app = Flask(__name__)
processor = IndoBertQAProcessor()

@app.route("/answer_question", method=["POST"])
def answer_question():
    data = request.get.json()
    context = data["context"]
    question = data["question"]
    answer = processor.answer_question(question, context)
    return jsonify({"answer": answer})

if __name__ == "__main__":
    app.run(debug=True)
