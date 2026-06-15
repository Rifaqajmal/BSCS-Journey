#include <stdio.h>

struct Student {
    int id;
    char name[20];
};

int main() {
    struct Student s;

    printf("Enter ID: ");
    scanf("%d", &s.id);

    printf("Enter Name: ");
    scanf("%s", s.name);

    printf("Student ID: %d\n", s.id);
    printf("Student Name: %s", s.name);

    return 0;
}