# from django.shortcuts import render
# import google.generativeai as genai

# def home(request):
#     if request.method == 'POST':
#         prompt = request.POST.get('prompt')
#         genai.configure(api_key="AIzaSyByLctahA8JjQ329kIg8bZRehFT_TBdiFY")
#         model = genai.GenerativeModel('gemini-pro')
#         response = model.generate_content(prompt)
#         context = {'response': response.text}
#         return render(request, 'index.html', context)
#     return render(request, 'index.html')
import json
from django.shortcuts import render
import google.generativeai as genai

# Initialize the GenerativeModel and configure it with API key
model = genai.GenerativeModel('gemini-pro')
genai.configure(api_key="AIzaSyByLctahA8JjQ329kIg8bZRehFT_TBdiFY")

# Instructions for the assistant

# Instructions for the assistant
instructions = '''
You are a website creation assistant and need to create a website for the user (who does not have any technological knowdledge) and provide a response with a JSON like the following:
{ "code": "you must send the HTML here of the website asked by the user", "text": "Response or text for the user goes here" }
You must add inline css for the code
'''

# Start the chat with the provided instructions
chat = model.start_chat(history=[{'parts': [{'text': instructions}], 'role': 'user'}])

# Define the home view
def home(request):
    if request.method == 'POST':
        # Get the user's prompt from the form
        prompt = request.POST.get('prompt')

        # Escape code and text to prevent loss
        prompt = json.dumps(prompt)

        # Add instructions at the end of the prompt
        prompt += ' / remember: ' + json.dumps(instructions)

        # Send the user's prompt to the chat model
        response = chat.send_message(prompt)

        # Parse the JSON response
        response_data = json.loads(response.text)
        
        # Extract HTML code and response text from the JSON
        code = response_data.get('code', '')
        text = response_data.get('text', '')

        # Prepare the context to render in the template
        context = {'code': code, 'text': text}
        return render(request, 'index.html', context)
    return render(request, 'index.html')