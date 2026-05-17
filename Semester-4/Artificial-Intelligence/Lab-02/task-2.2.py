# String manipulation in Python

# 1. Sorting a string
def sort_string(s):
    return ''.join(sorted(s))  # sorted() returns a list, so we join it back to a string

# 2. Reversing a string
def reverse_string(s):
    return s[::-1]

# 3. Slicing a string
def slice_string(s, start, end):
    return s[start:end]  # slice from index 'start' to 'end-1'

# 4. Loop through a string
def loop_string(s):
    for char in s:
        print(char)

# Test the functions
my_string = "python"

# Sorting the string
sorted_string = sort_string(my_string)
print("Sorted string:", sorted_string)

# Reversing the string
reversed_string = reverse_string(my_string)
print("Reversed string:", reversed_string)

# Slicing the string (e.g., slice from index 1 to 4)
sliced_string = slice_string(my_string, 1, 4)
print("Sliced string:", sliced_string)

# Loop through the string
print("Looping through the string:")
loop_string(my_string)
