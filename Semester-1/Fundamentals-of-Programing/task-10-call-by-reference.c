#include <stdio.h>

void change(int *x) {
    *x = *x + 10;
}

int main() {
    int num = 5;

    change(&num);

    printf("Value after function call: %d", num);

    return 0;
}