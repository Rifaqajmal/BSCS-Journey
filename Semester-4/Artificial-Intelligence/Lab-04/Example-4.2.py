# Read questions and answers from files
questions = [line.strip() for line in open('questions.txt')]
answers = [line.strip() for line in open('answers.txt')]

score = 0  # Keep track of the score
total_questions = len(questions)

# Ask each question
for i in range(total_questions):
    print(f"Question {i + 1}: {questions[i]}")
    user_answer = input("Your answer: ").strip()

    # Check if the answer is correct
    if user_answer.lower() == answers[i].lower():
        print("Correct!\n")
        score += 1
    else:
        print(f"Wrong. The correct answer is: {answers[i]}\n")

# Show final score
print(f"You got {score} out of {total_questions} questions right.")
