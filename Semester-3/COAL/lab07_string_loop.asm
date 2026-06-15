.MODEL SMALL
.STACK 100H

.DATA
str DB "HELLO$"

.CODE
MAIN PROC
    MOV AX, @DATA
    MOV DS, AX

    LEA SI, str

PRINT_LOOP:
    MOV DL, [SI]
    CMP DL, '$'
    JE END_PRINT

    MOV AH, 02H
    INT 21H

    INC SI
    JMP PRINT_LOOP

END_PRINT:
    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN