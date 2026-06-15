#include <stdio.h>

// Function to check leap year
int isLeapYear(int year) {
    if ((year % 4 == 0 && year % 100 != 0) || (year % 400 == 0))
        return 1;
    else
        return 0;
}

// Function to get days in a month
int getDaysInMonth(int month, int year) {
    if (month == 2) {
        if (isLeapYear(year))
            return 29;
        else
            return 28;
    }
    else if (month == 4 || month == 6 || month == 9 || month == 11)
        return 30;
    else
        return 31;
}

int main() {
    int b_day, b_month, b_year;
    int c_day, c_month, c_year;
    int age_year, age_month, age_day;

    printf("=====================================\n");
    printf("   Welcome to My Age Calculator\n");
    printf("=====================================\n\n");

    printf("Please enter date in NUMBERS only\n");
    printf("Example:\n");
    printf("Day = 26\nMonth = 12\nYear = 2002\n\n");

    // Input Date of Birth
    printf("Enter your Date of Birth\n");
    printf("Day: ");
    scanf("%d", &b_day);
    printf("Month: ");
    scanf("%d", &b_month);
    printf("Year: ");
    scanf("%d", &b_year);

    // Input Current Date
    printf("\nEnter Current Date\n");
    printf("Day: ");
    scanf("%d", &c_day);
    printf("Month: ");
    scanf("%d", &c_month);
    printf("Year: ");
    scanf("%d", &c_year);

    // Age calculation
    age_year = c_year - b_year;
    age_month = c_month - b_month;
    age_day = c_day - b_day;

    if (age_day < 0) {
        age_month--;

        if (c_month == 1)
            age_day += getDaysInMonth(12, c_year - 1);
        else
            age_day += getDaysInMonth(c_month - 1, c_year);
    }

    if (age_month < 0) {
        age_month += 12;
        age_year--;
    }

    // Output
    printf("\nYour Age is:\n");
    printf("%d Years, %d Months, %d Days\n", age_year, age_month, age_day);

    printf("\nThank you for using my Age Calculator!\n");

    return 0;
}