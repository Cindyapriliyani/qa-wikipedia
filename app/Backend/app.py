from flask import Flask, request, render_template, jsonify
import wikipediaapi
from transformers import pipeline
import torch

app = Flask(__name__)

#inisialisasi pipeline QA dengan model IndoBERT
qa_pipeline = pipeline(
    "question-answering",
    model="Rifky/Indobert-QA",
    tokenizer="Rifky/Indobert-QA"
)

#inisialisasi wikipedia api
wiki_wiki = wikipediaapi.Wikipedia('id')

@app.route("/", methods=["GET", "POST"])
def index():
    context = ""
    question = ""
    answer = ""
    if request.method == "POST":
        keyword = request.form["keyword"]
        question = request.form["question"]
        page = wiki_wiki.page(keyword)
        if page.exists():
            context = page.text[:2000] # mengambil 2000 karakter pertama dari artikel wikipedia
            result = qa_pipeline({
                'context': context,
                'question': question
            })
            answer = result["answer"]
        else:
            context = "Artikel tidak ditemukan di Wikipedia."
            answer = "Tidak ada jawaban karena artikel tidak ditemukan."
        return render_template("index.html", keyword=keyword, context=context, question=question, answer=answer)
    
    if __name__ == "__main__":
        app.run(debug=True)