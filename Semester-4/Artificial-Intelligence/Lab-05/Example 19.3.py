# Creating a set
S = {1, 2, 3, 4, 5}
print("Set S:", S)

# Creating an empty set
empty_set = set()
print("Empty Set:", empty_set)

# Removing duplicates from a list using a set
L = [1, 4, 4, 4, 5, 1, 2, 1, 3]
unique_elements = list(set(L))
print("List with duplicates removed:", unique_elements)

# Converting a string to a set (removes duplicates and keeps unique characters)
char_set = set('this is a test')
print("Unique characters in the string:", char_set)

# Set Union
A = {1, 2, 3}
B = {3, 4}
union_set = A | B  # union operator '|'
print("Union of A and B:", union_set)

# Set Intersection
intersection_set = A & B  # intersection operator '&'
print("Intersection of A and B:", intersection_set)

# Set Difference
difference_set = A - B  # difference operator '-'
print("Difference between A and B:", difference_set)

# Set Symmetric Difference
symmetric_diff_set = A ^ B  # symmetric difference operator '^'
print("Symmetric Difference between A and B:", symmetric_diff_set)

# Check if an element is in a set
print("Is 3 in A?", 3 in A)  # Output: True

# Adding and Removing elements from a set
A.add(5)
print("A after adding 5:", A)

A.remove(5)  # Removes 5 from the set
print("A after removing 5:", A)

# Checking if a set is a subset or superset
print("Is A a subset of B?", A.issubset(B))  # Output: False
print("Is A a superset of B?", A.issuperset(B))  # Output: False

# Set Comprehension
squares = {i**2 for i in range(12)}  # Set comprehension to generate squares
print("Squares set:", squares)
