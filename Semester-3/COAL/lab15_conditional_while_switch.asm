.MODEL SMALL
.STACK 100H

.DATA
msg DB "Switch Case Example$"

.CODE
MAIN PROC
    MOV AX, @DATA
    MOV DS, AX

    MOV AL, 2

    CMP AL, 1
    JE CASE1

    CMP AL, 2
    JE CASE2

    JMP END_CASE

CASE1:
    MOV DL, 'A'
    JMP PRINT

CASE2:
    MOV DL, 'B'

PRINT:
    MOV AH, 02H
    INT 21H

END_CASE:
    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN