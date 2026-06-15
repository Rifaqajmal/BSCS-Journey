#include <stdio.h>

void change(int x) {
    x = x + 10;
    printf("Value inside function: %d\n", x);
}

int main() {
    int num = 5;

    change(num);

    printf("Value in main function: %d", num);

    return 0;
}