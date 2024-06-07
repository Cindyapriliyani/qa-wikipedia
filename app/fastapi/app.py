from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from transformers import BertTokenizer, BertForQuestionAnswering
import torch
import wikipediaapi

app = FastAPI()

model_name = "indobenchmark/indobert-base-p2"
tokenizer = BertTokenizer.from_pretrained(model_name)
model = BertForQuestionAnswering.from_pretrained(model_name)

wiki_wiki = wikipediaapi.Wikipedia('id')

class QARequest(BaseModel):
    question: str
    keyword: str

@app.post("/predict/")
async def predict_qa(request: QARequest):
    # Ambil konteks dari Wikipedia berdasarkan keyword
    page = wiki_wiki.page(request.keyword)
    if not page.exists():
        raise HTTPException(status_code=404, detail="Wikipedia page not found")

    context = page.text

    inputs = tokenizer.encode_plus(request.question, context, return_tensors="pt")
    input_ids = inputs["input_ids"].tolist()[0]

    outputs = model(**inputs)
    answer_start_scores = outputs.start_logits
    answer_end_scores = outputs.end_logits

    answer_start = torch.argmax(answer_start_scores)
    answer_end = torch.argmax(answer_end_scores) + 1

    answer = tokenizer.convert_tokens_to_string(tokenizer.convert_ids_to_tokens(input_ids[answer_start:answer_end]))

    return {"answer": answer, "context": context}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
