.MODEL SMALL
.STACK 100H

.CODE
MAIN PROC

    MOV AL, 6
    MOV BL, 2

    MUL BL      ; AX = AL * BL

    MOV AX, 10
    MOV BL, 2
    DIV BL      ; AL = quotient

    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN