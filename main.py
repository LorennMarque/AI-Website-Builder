import google.generativeai as genai

genai.configure(api_key="AIzaSyByLctahA8JjQ329kIg8bZRehFT_TBdiFY")
model = genai.GenerativeModel("gemini-pro")

response = model.generate_content(input("Prompt="))
print(response.text)

