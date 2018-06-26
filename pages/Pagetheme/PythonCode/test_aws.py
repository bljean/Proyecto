import boto3
from pprint import pprint
client = boto3.client('rekognition')
response = client.list_collections()


pprint(response['CollectionIds'])
