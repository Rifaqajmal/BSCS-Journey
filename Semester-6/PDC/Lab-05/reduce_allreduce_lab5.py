from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

value = rank + 1

sum_result = comm.reduce(value, op=MPI.SUM, root=0)

all_sum = comm.allreduce(value, op=MPI.SUM)

if rank == 0:
    print("Reduce result at root:", sum_result)

print("Process", rank, "Allreduce result:", all_sum)