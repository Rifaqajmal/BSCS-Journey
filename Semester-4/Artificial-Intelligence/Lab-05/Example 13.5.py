# Global variable
time_left = 30

# Function to reset the global variable
def reset():
    global time_left  # Tells Python that we're using the global variable time_left
    time_left = 0

# Function to print the global variable
def print_time():
    print(time_left)  # Prints the current value of time_left

# Function with a local variable
def func1():
    i = 10  # Local variable 'i' inside func1
    print(f"Local i inside func1: {i}")

def func2():
    i = 100  # Local variable 'i' inside func2
    func1()  # Calls func1, which has its own local 'i'
    print(f"Local i inside func2: {i}")

# Example showing local and global variable usage
print_time()  # Prints global time_left, initially 30
reset()  # Resets the global time_left to 0
print_time()  # Prints global time_left, now 0

# Calling func2, which also demonstrates local variables
func2()

# Demonstrating mutable vs immutable arguments
def func3(x):
    x = x + 1  # This changes the local 'x', does not affect the original variable
    return x

def func4(L):
    L = L + [1]  # This changes the local 'L', doesn't affect the original list
    print(f"Modified List in func4: {L}")

# Testing with immutable (integer) and mutable (list) objects
a = 3
M = [1, 2, 3]
a = func3(a)  # a is changed to 4 because integers are immutable
func4(M)  # M remains unchanged because lists are mutable, but we only modified the local copy
print(f"Original a: {a}")  # a is 4
print(f"Original M: {M}")  # M is unchanged, still [1, 2, 3]
