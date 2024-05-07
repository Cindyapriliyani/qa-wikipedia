import requests
from bs4 import BeautifulSoup
from indobert_qa.indobert_qa import IndobertQA

# Identifikasi topik
topic = "Mesin Pembelajaran"

# Ambil daftar artikel terkait topik dari Wikipedia (contoh menggunakan requests dan BeautifulSoup)
response = requests.get(f"https://id.wikipedia.org/wiki/Kategori:{topic}")
soup = BeautifulSoup(response.text, "html.parser")
article_links = [link.get("href") for link in soup.find_all("a", href=True) if "/wiki/" in link.get("href")]

# Ambil teks semua artikel terkait topik
all_article_text = ""
for link in article_links:
    article_response = requests.get(f"https://id.wikipedia.org{link}")
    article_soup = BeautifulSoup(article_response.text, "html.parser")
    article_text = article_soup.find("div", class_="mw-parser-output").get_text()
    all_article_text += article_text + "\n"

# Gunakan IndobertQA
indobert_qa = IndobertQA()
question = "Apa itu Machine Learning?"
answer = indobert_qa.answer_question(question, all_article_text)
print("Jawaban:", answer)
