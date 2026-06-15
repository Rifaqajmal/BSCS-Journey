#include <stdio.h>

int main() {
    int a, b;

    printf("Enter first number: ");
    scanf("%d", &a);

    printf("Enter second number: ");
    scanf("%d", &b);

    if(a > b)
        printf("Largest number is %d", a);
    else
        printf("Largest number is %d", b);

    return 0;
}