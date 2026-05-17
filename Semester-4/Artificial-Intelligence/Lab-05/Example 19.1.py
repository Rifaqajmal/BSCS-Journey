# Mutability and References with Strings
s = 'Hello'
copy = s  # copy references the same 'Hello' string
s = s + '!!!'  # Creates a new string object 'Hello!!!'
print('s is now:', s, 'Copy:', copy)  # 's' is 'Hello!!!', 'copy' remains 'Hello'

# Mutability and References with Lists
L = [1, 2, 3]
copy_list = L  # copy_list references the same list object as L
L[0] = 9  # Modifies the list L in place
print('L is now:', L, 'Copy of L:', copy_list)  # Both L and copy_list show [9, 2, 3]

# Making a proper copy of a list using slicing
L = [1, 2, 3]
copy_list = L[:]  # Makes a new copy of the list
L[0] = 9  # Modifies only L
print('L after modification:', L)  # [9, 2, 3]
print('Copy of L remains:', copy_list)  # [1, 2, 3] - copy is unaffected

# References with Variables
x = 487
y = x  # y references the same object 487
print(f'x and y reference the same object: {x == y}')  # True, both refer to 487

# Reassigning 'x' to a new value
x = 721  # Now x points to a new object 721
print(f'x now points to: {x}')  # 721
print(f'y still points to the original: {y}')  # 487, y remains the same
