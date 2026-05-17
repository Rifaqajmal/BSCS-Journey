# List of characters
letters = ['a', 'b', 'c', 'a', 'd', 'a', 'e']

# Initialize count for 'a' characters
count_a = 0

for letter in letters:
    if letter != 'a':  # Skip the rest of the loop if it's not 'a'
        continue
    count_a += 1  # Only increments if letter is 'a'

print(f"The letter 'a' appears {count_a} times.")
