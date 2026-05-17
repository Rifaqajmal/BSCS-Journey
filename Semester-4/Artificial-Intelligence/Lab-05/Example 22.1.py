#Functions as Variables Example:


def square(x):
    return x * x

g = square
print(g(3))  # Same as calling square(3)
