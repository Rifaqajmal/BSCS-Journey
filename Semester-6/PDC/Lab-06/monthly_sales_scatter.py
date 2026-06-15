from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

monthly_sales = None

if rank == 0:
    monthly_sales = [12000, 15000, 18000, 20000] 

sales = comm.scatter(monthly_sales, root=0)

print("Process", rank, "received monthly sales:", sales)