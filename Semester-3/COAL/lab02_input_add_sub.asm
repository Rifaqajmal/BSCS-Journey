.MODEL SMALL
.STACK 100H

.DATA
msg1 DB "Enter first number: $"
msg2 DB 10,13,"Enter second number: $"
msg3 DB 10,13,"Result: $"

.CODE
MAIN PROC
    MOV AX, @DATA
    MOV DS, AX

    ; input first number
    MOV AH, 01H
    INT 21H
    SUB AL, 30H
    MOV BL, AL

    ; input second number
    MOV AH, 01H
    INT 21H
    SUB AL, 30H

    ; addition
    ADD BL, AL

    ; print result message
    MOV AH, 09H
    LEA DX, msg3
    INT 21H

    ; print result
    ADD BL, 30H
    MOV DL, BL
    MOV AH, 02H
    INT 21H

    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN