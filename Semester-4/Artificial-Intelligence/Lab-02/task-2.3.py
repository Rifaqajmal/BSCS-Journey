# 1. Creating a list
my_list = [5, 2, 9, 1, 5, 6]

# 2. Creating a 2-dimensional list (list of lists)
my_2d_list = [
    [5, 2, 9],
    [1, 5, 6],
    [8, 3, 7]
]

# 3. Sorting a list
def sort_list(lst):
    return sorted(lst)

# Sorting a 2D list by sorting each row
def sort_2d_list(lst):
    return [sorted(row) for row in lst]

# 4. Extending a list (adding multiple elements to a list)
def extend_list(lst, new_elements):
    lst.extend(new_elements)  # modifies the list in place

# 5. Appending an element to a list
def append_to_list(lst, new_element):
    lst.append(new_element)  # adds a single element at the end

# 6. Appending a new row to a 2D list
def append_to_2d_list(lst, new_row):
    lst.append(new_row)

# Test the operations
print("Original List:", my_list)
print("Original 2D List:", my_2d_list)

# Sorting
sorted_list = sort_list(my_list)
sorted_2d_list = sort_2d_list(my_2d_list)
print("\nSorted List:", sorted_list)
print("Sorted 2D List:", sorted_2d_list)

# Extending the list
extend_list(my_list, [10, 11])
print("\nList after extend:", my_list)

# Appending to the list
append_to_list(my_list, 12)
print("List after append:", my_list)

# Appending to 2D list
append_to_2d_list(my_2d_list, [9, 4, 6])
print("2D List after appending a new row:", my_2d_list)

# 1. Deleting an element from a list by index using del
def delete_by_index(lst, index):
    del lst[index]  # removes the element at the specified index

# 2. Deleting an element from a list by value using remove
def delete_by_value(lst, value):
    lst.remove(value)  # removes the first occurrence of the specified value

# 3. Deleting an element from a list using pop (and returning it)
def pop_element(lst, index=None):
    if index is not None:
        return lst.pop(index)  # removes and returns the element at the specified index
    return lst.pop()  # removes and returns the last element if no index is given

# 4. Deleting an entire row from a 2D list
def delete_row_2d_list(lst, row_index):
    del lst[row_index]  # deletes the row at the specified index

# Test delete operations
my_list = [5, 2, 9, 1, 5, 6]
my_2d_list = [
    [5, 2, 9],
    [1, 5, 6],
    [8, 3, 7]
]

print("Original List:", my_list)
print("Original 2D List:", my_2d_list)

# 1. Delete an element by index
delete_by_index(my_list, 2)  # deletes element at index 2 (which is 9)
print("\nList after deleting by index (2):", my_list)

# 2. Delete an element by value
delete_by_value(my_list, 5)  # removes the first occurrence of 5
print("List after deleting by value (5):", my_list)

# 3. Pop an element (returns the popped element)
popped_element = pop_element(my_list, 1)  # removes and returns element at index 1
print("Popped element:", popped_element)
print("List after popping element at index 1:", my_list)

# 4. Delete a row from a 2D list
delete_row_2d_list(my_2d_list, 1)  # deletes the row at 

