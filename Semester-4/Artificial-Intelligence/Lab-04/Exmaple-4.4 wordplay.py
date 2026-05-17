import re

# Load the paragraph from the file and split it into words
with open('wordlist.txt', 'r') as file:
    text = file.read()

# Use regex to split text into words, ignoring punctuation
wordlist = re.findall(r'\b\w+\b', text)

# Example 1: Print all three-letter words
print("Three-letter words:")
for word in wordlist:
    if len(word) == 3:
        print(word)

# Example 2: Print all the words that start with 'gn' or 'kn'
print("\nWords that start with 'gn' or 'kn':")
for word in wordlist:
    if word[:2] == 'gn' or word[:2] == 'kn':
        print(word)

# Example 3: Determine what percentage of words start with a vowel
count = 0
for word in wordlist:
    if word[0].lower() in 'aeiou':
        count += 1
percentage = 100 * count / len(wordlist)
print(f"\nPercentage of words that start with a vowel: {percentage:.2f}%")

# Example 4: Print all 7-letter words that start with 'th' and end with 'ly'
print("\n7-letter words that start with 'th' and end with 'ly':")
for word in wordlist:
    if len(word) == 7 and word[:2] == 'th' and word[-2:] == 'ly':
        print(word)

# Example 5: Print the first ten words that start with 'q'
print("\nFirst ten words that start with 'q':")
q_words = [word for word in wordlist if word[0].lower() == 'q']
print(q_words[:10])

# Example 6: Find the longest word that can be made using only the letters a, b, c, d, and e
largest = 0
largest_word = ''
for word in wordlist:
    for c in word:
        if c not in 'abcde':
            break
    else:
        if len(word) > largest:
            largest = len(word)
            largest_word = word
print(f"\nLongest word using only 'a', 'b', 'c', 'd', 'e': {largest_word}")
