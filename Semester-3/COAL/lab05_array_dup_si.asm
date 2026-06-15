.MODEL SMALL
.STACK 100H

.DATA
arr DB 1,2,3,4,5

.CODE
MAIN PROC
    MOV AX, @DATA
    MOV DS, AX

    LEA SI, arr
    MOV CX, 5

PRINT_LOOP:
    MOV DL, [SI]
    ADD DL, 30H
    MOV AH, 02H
    INT 21H

    INC SI
    LOOP PRINT_LOOP

    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN