from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

data = rank

sum_result = comm.reduce(data, op=MPI.SUM, root=0)

prod_result = comm.reduce(data if rank != 0 else 1, op=MPI.PROD, root=0)

if rank == 0:
    print("Sum of ranks:", sum_result)
    print("Product of ranks:", prod_result)