def safe_input():
    # This function ensures that input is safely converted to an integer
    try:
        num = int(input("Enter an integer: "))
        return num
    except ValueError:
        print("Invalid input! Please enter a valid integer.")
        return safe_input()

def dynamic_code_execution():
    # Ask the user for a mathematical expression and evaluate it using eval
    expr = input("Enter a mathematical expression (e.g., 3*(4+5)): ")
    try:
        result = eval(expr)
        print(f"The result of the expression is: {result}")
    except Exception as e:
        print(f"Error in evaluating the expression: {e}")
    
    # Use exec to run a block of code entered by the user
    code = input("Enter a block of Python code to execute (e.g., 'x = 5; print(x)'): ")
    try:
        exec(code)
    except Exception as e:
        print(f"Error in executing the code: {e}")

# Example use case:
print("Welcome to the Dynamic Code Execution Demo!")

# Securely get an integer
user_number = safe_input()
print(f"You entered: {user_number}")

# Dynamically execute code
dynamic_code_execution()

