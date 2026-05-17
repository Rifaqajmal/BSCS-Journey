import string

# Read the content of the file
with open('file.txt', 'r') as file:
    text = file.read()

# Convert the text to uppercase
text = text.upper()

# Remove punctuation using translation
translator = str.maketrans('', '', string.punctuation)
text = text.translate(translator)

# Split the text into words
words = text.split()

# Print the processed text
print(words)
