.MODEL SMALL
.STACK 100H

.DATA
arr DB 1,2,3

.CODE
MAIN PROC

    MOV AX, @DATA
    MOV DS, AX

    MOV AL, [arr]
    MOV BL, arr+1

    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN