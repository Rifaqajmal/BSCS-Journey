.MODEL SMALL
.STACK 100H

.DATA
msg DB "Hello COAL Lab 01$"

.CODE
MAIN PROC
    MOV AX, @DATA
    MOV DS, AX

    MOV AH, 09H
    LEA DX, msg
    INT 21H

    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN