from google.cloud import language_v1


def sample_analyze_sentiment(text_content):
    client = language_v1.LanguageServiceClient.from_service_account_json('./python/apikey.json')

    type_ = language_v1.Document.Type.PLAIN_TEXT

    language = "en"
    document = {"content": text_content, "type_": type_, "language": language}

    encoding_type = language_v1.EncodingType.UTF8

    response = client.analyze_sentiment(request={'document': document, 'encoding_type': encoding_type})

    # Get sentiment for all sentences in the document
    for sentence in response.sentences:
        if sentence.sentiment.score < 0:
            return ":("
        else:
            return ":)"
