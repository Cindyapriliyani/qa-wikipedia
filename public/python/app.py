from flask import Flask, jsonify
import requests
from lxml import html

app = Flask(__name__)

def get_wikipedia_text(subject):
    url ='https://en.wikipedia.org/w/api.php'
    params = {
        'action': 'parse',
        'format' : 'json',
        'page' : subject,
        'prop' : 'text',
        'redirects' : ''
    }

    response = requests.get(url, params=params).json()
    raw_html = response['parse']['text']['*']
    document = html.document_fromstring(raw_html)
    text =''
    for p in document.xpath('//p'):
        text += p.text_content() + '\n'
    return text

@app.route('/api/wikipedia/<subject>')
def wikipedia_api(subject):
    wikipedia_text = get_wikipedia_text(subject)
    return jsonify({'text':wikipedia_text})

if __name__ == '__main__':
    app.run(debug=True)