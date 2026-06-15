#include <iostream>
#include <cstdlib>
using namespace std;

class Car {
public:
    int xPosition, yPosition, speed;

    void currState() {
        cout << xPosition << " " << yPosition << " " << speed << endl;
    }
};

int main() {
    Car cars[10];

    for (int i = 0; i < 10; i++) {
        cars[i].xPosition = rand() % 10;
        cars[i].yPosition = rand() % 10;
        cars[i].speed = rand() % 5;
    }

    Car *p = cars;

    for (int i = 0; i < 10; i++) {
        (p + i)->currState();
    }

    return 0;
}