# 1. Basic List Comprehension: Squares of numbers from 0 to 9
squares = [x**2 for x in range(10)]
print("Squares:", squares)

# 2. List Comprehension with a Condition (Filtering): Even numbers from 0 to 9
evens = [x for x in range(10) if x % 2 == 0]
print("Even numbers:", evens)

# 3. Nested List Comprehension (2D Lists): Create a 3x3 matrix
matrix = [[i + j for j in range(3)] for i in range(3)]
print("2D List (Matrix):", matrix)

# 4. Flattening a 2D List: Flatten the matrix into a 1D list
flattened = [num for row in matrix for num in row]
print("Flattened 2D List:", flattened)

# 5. List Comprehension with Multiple Conditions: Squares of even numbers greater than 0
even_squares = [x**2 for x in range(10) if x % 2 == 0 and x > 0]
print("Even squares:", even_squares)

# 6. Applying a Function in List Comprehension: Using a function to square numbers
def square(x):
    return x * x

squared_list = [square(x) for x in range(10)]
print("Squared using function:", squared_list)

# 7. List Comprehension with Strings: Create a list of characters from a string
chars = [char for char in "hello"]
print("List of characters:", chars)

# 8. Using if-else in List Comprehension: Replace odd numbers with -1
modified_list = [x if x % 2 == 0 else -1 for x in range(10)]
print("Modified list (even stays, odd becomes -1):", modified_list)

# 9. List Comprehension with Multiple Loops: Cartesian product of two lists
cartesian_product = [(x, y) for x in [1, 2, 3] for y in ['a', 'b', 'c']]
print("Cartesian product:", cartesian_product)
