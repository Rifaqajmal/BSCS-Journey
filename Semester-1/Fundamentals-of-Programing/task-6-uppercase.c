#include <stdio.h>
#include <ctype.h>

int main() {
    char ch;

    printf("Enter a character: ");
    scanf("%c", &ch);

    ch = toupper(ch);

    printf("Uppercase character: %c", ch);

    return 0;
}