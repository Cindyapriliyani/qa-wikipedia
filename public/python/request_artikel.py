import requests
from lxml import html

subject = 'Python (programing language)'
url = 'https://en.wikipedia.org/w/api.php'
params = {
    'action':'parse',
    'format': 'json',
    'page': subject,
    'prop':'text',
    'redirects':''
}

response = requests.get(url, params=params).json()
raw_html = response['parse']['text']['*']
document = html.document_fromstring(raw_html)

text = ''
for p in document.xpath('//p'):
    text += p.text_content() + '\n'
print(text)