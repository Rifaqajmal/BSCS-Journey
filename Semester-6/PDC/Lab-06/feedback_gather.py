from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

feedback = [
    ("Great app",5),
    ("Needs improvement",3),
    ("Good features",4),
    ("Average experience",2)
]

my_feedback = feedback[rank]

all_feedback = comm.gather(my_feedback, root=0)

if rank == 0:
    print("Collected Feedback:")
    for f in all_feedback:
        print("Message:", f[0], "| Rating:", f[1])