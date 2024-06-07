from flask import Flask, request, jsonify, render_template
from transformers import pipeline
import wikipediaapi

app = Flask(__name__)

# Inisialisasi pipeline QA dengan model IndoBERT
qa_pipeline = pipeline(
    "question-answering",
    model="Rifky/Indobert-QA",
    tokenizer="Rifky/Indobert-QA"
)

# Inisialisasi Wikipedia API
wiki_wiki = wikipediaapi.Wikipedia('id')

@app.route("/")
def home():
    return render_template("home.blade")

@app.route("/api/answer", methods=["POST"])
def answer():
    data = request.json()
    question = data.get('question')
    context = data.get('context')


    result = qa_pipeline({
        'context': context,
        'question': question
    })

    # jawaban = result['answer'] if 'answer' in result else "Maaf, saya tidak bisa menemukan jawaban atas pertanyaan Anda dalam deskripsi tersebut."
    return jsonify({'answer': result['answer']})

@app.route('/api/search', methods=['POST'])
def search():
    topic = request.args.get('topic')
    wiki_wiki = wikipediaapi.Wikipedia('id')
    page = wiki_wiki.page(topic)

    if not page.exists():
        return render_template('home.blade', error='Topik tidak ditemukan di Wikipedia')

    description = page.summary

    return render_template('home.blade', description=description)

if __name__ == '__main__':
    app.run(debug=True)