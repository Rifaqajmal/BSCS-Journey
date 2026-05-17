import string

# 1. Function to replace punctuation with a specified character
def replace_punctuation(text, replacement_char=" "):
    return ''.join(replacement_char if char in string.punctuation else char for char in text)

# Example sentence with punctuation
sentence = "Hello, world! How's it going? (Are you coding... or debugging?)"

# Replacing punctuation with a space
replaced_sentence = replace_punctuation(sentence, " ")
print("Sentence with punctuation replaced by spaces:", replaced_sentence)

# Replacing punctuation with an underscore
replaced_sentence_underscore = replace_punctuation(sentence, "_")
print("Sentence with punctuation replaced by underscores:", replaced_sentence_underscore)
