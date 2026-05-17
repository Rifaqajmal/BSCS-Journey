# Creating a list and a tuple with the same elements
L = [1, 2, 3]
t = (1, 2, 3)

# Accessing elements using indexing
print("First element of tuple:", t[0])  # Output: 1
print("Second element of list:", L[1])  # Output: 2

# Slicing a tuple
print("Slice of the tuple:", t[1:3])  # Output: (2, 3)

# Tuple methods
print("Length of the tuple:", len(t))  # Output: 3
print("Count of '1' in tuple:", t.count(1))  # Output: 1
print("Index of '3' in tuple:", t.index(3))  # Output: 2

# Tuples cannot be modified (no sort or reverse methods)
# This would cause an error:
# t.sort()  # Error: 'tuple' object has no attribute 'sort'

# Creating a tuple from a list and a string
t1 = tuple([1, 2, 3])
t2 = tuple('abcde')

# Displaying the created tuples
print("Tuple from list:", t1)  # Output: (1, 2, 3)
print("Tuple from string:", t2)  # Output: ('a', 'b', 'c', 'd', 'e')

# Example of using a tuple as a dictionary key
grades = {('John', 'Ann'): 95, ('Mike', 'Tazz'): 87}
print("Grades for John and Ann:", grades[('John', 'Ann')])  # Output: 95

# Using tuple to swap values
a, b = 5, 10
a, b = b, a  # Swapping using tuple unpacking
print("a:", a, "b:", b)  # Output: a: 10 b: 5
