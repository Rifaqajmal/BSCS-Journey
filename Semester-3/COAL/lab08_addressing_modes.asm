.MODEL SMALL
.STACK 100H

.DATA
var DB 10

.CODE
MAIN PROC
    MOV AX, @DATA
    MOV DS, AX

    MOV AL, var      ; direct
    LEA SI, var      ; indirect

    MOV AH, 4CH
    INT 21H
MAIN ENDP
END MAIN