from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

value = rank + 1

product = comm.reduce(value, op=MPI.PROD, root=0)

if rank == 0:
    print("Product of all values:", product)