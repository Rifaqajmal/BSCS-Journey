#include <iostream>
using namespace std;

class Car {
public:
    int xPosition, yPosition, speed;

    Car() {
        xPosition = 0;
        yPosition = 0;
        speed = 0;
    }

    void accelerate() { speed++; }
    void decelerate() { if (speed > 0) speed--; }

    void turnLeft() { xPosition--; }
    void turnRight() { xPosition++; }

    void currState() {
        cout << xPosition << " " << yPosition << " " << speed << endl;
    }
};