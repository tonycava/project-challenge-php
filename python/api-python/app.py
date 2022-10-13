from flask import Flask, request, jsonify

from ..analyzeSentiment import sample_analyze_sentiment

app = Flask(__name__)


@app.route('/')
def hello_world():
    return 'Welcome to our API !'


@app.route('/get-emotion', methods=["POST"])
def testpost():
    input_json = request.get_json(force=True)
    dictToReturn = {'emotion': sample_analyze_sentiment(input_json['emotion'])}
    return jsonify(dictToReturn)
