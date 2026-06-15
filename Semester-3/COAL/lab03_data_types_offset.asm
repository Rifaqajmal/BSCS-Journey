.MODEL SMALL
.STACK 100H

.DATA
num DB 5
arr DB 1,2,3,4,5

.CODE
MAIN PROC
    MOV AX, @DATA
    MOV DS, AX

    ; load address using OFFSET
    LEA SI, arr

    MOV AL, [SI]   ; first element
    MOV BL, num    ; variable access

    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN