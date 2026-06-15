from mpi4py import MPI
import random

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

value = random.randint(10,100)

print("Process", rank, "value:", value)

minimum = comm.allreduce(value, op=MPI.MIN)

print("Process", rank, "minimum value:", minimum)