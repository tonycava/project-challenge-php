FROM python:3.8-alpine

WORKDIR /app

COPY requirements.txt requirements.txt

RUN pip install -r requirements.txt

COPY . .

RUN cd /app/api-python

CMD [ "python3", "-m" , "flask", "run", "--host=0.0.0.0"]