# Get the Unicode code point of a character
char_value = ord('A')
print("Unicode value of 'A':", char_value)  # Output: 65

# Get the character corresponding to a Unicode code point
char_from_value = chr(65)
print("Character for Unicode value 65:", char_from_value)  # Output: 'A'

# Print the first 1000 Unicode characters
print(''.join([chr(i) for i in range(1000)]))
