
def add(a, b):
    return a + b


# Function to check if a number is even
def is_even(n):
    return n % 2 == 0


# Function to return both the sum and product of two numbers
def sum_and_product(a, b):
    return a + b, a * b


# Example usage
result = add(3, 5)
print(result)


even_check = is_even(4)
print(even_check)

sum_val, product_val = sum_and_product(3, 4)  # Gets both sum and product
print(f"Sum: {sum_val}, Product: {product_val}")
