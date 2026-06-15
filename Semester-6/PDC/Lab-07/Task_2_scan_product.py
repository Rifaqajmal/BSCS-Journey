from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

value = rank + 1

result = comm.scan(value, op=MPI.PROD)

print("Process", rank, "cumulative product:", result)